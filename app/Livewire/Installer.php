<?php

namespace App\Livewire;

use PDO;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class Installer extends Component
{
    public int $currentStep = 1;

    // Database Config
    public $db_host = '127.0.0.1';
    public $db_port = '3306';
    public $db_database = '';
    public $db_username = '';
    public $db_password = '';

    // Admin Account
    public $admin_name = '';
    public $admin_email = '';
    public $admin_password = '';
    public $admin_password_confirmation = '';

    // State
    public $requirements = [];
    public $permissions = [];
    public $connectionError = '';
    public $migrationError = '';
    public $isMigrating = false;

    protected $rules = [
        3 => [
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ],
        4 => [
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
        ]
    ];

    public function mount()
    {
        $this->checkRequirements();
        $this->checkPermissions();
    }

    public function checkRequirements()
    {
        $minVersionPHP = '8.2.0';
        $fullVersionPHP = phpversion();

        $this->requirements = [
            'php' => [
                'label' => 'PHP Version >= ' . $minVersionPHP,
                'status' => version_compare($fullVersionPHP, $minVersionPHP, '>='),
                'current' => $fullVersionPHP
            ],
            'BCMath' => ['label' => 'BCMath Extension', 'status' => extension_loaded('BCMath')],
            'Ctype' => ['label' => 'Ctype Extension', 'status' => extension_loaded('Ctype')],
            'Fileinfo' => ['label' => 'Fileinfo Extension', 'status' => extension_loaded('Fileinfo')],
            'OpenSSL' => ['label' => 'OpenSSL Extension', 'status' => extension_loaded('openssl')],
            'PDO' => ['label' => 'PDO Extension', 'status' => extension_loaded('pdo')],
            'Mbstring' => ['label' => 'Mbstring Extension', 'status' => extension_loaded('mbstring')],
            'Tokenizer' => ['label' => 'Tokenizer Extension', 'status' => extension_loaded('tokenizer')],
            'JSON' => ['label' => 'JSON Extension', 'status' => extension_loaded('json')], // Note: often built-in
            'XML' => ['label' => 'XML Extension', 'status' => extension_loaded('xml')],
            'cURL' => ['label' => 'cURL Extension', 'status' => extension_loaded('curl')],
            'GD' => ['label' => 'GD Library', 'status' => extension_loaded('gd')],
        ];
    }

    public function checkPermissions()
    {
        $folders = [
            'storage/framework/' => '775',
            'storage/logs/' => '775',
            'bootstrap/cache/' => '775',
        ];

        foreach ($folders as $folder => $permission) {
            $path = base_path($folder);
            $isWritable = is_writable($path);

            // If directory doesn't exist, try to create it or mark as failed
            if (!file_exists($path)) {
                @mkdir($path, 0755, true);
                $isWritable = is_writable($path);
            }

            $this->permissions[] = [
                'folder' => $folder,
                'permission' => $permission,
                'isSet' => $isWritable
            ];
        }
    }

    protected function configureDatabaseRuntime()
    {
        // Explicitly capture values to avoid any magic get confusion
        $host = (string) $this->db_host;
        $port = (string) $this->db_port;
        $database = (string) $this->db_database;
        $username = (string) $this->db_username;
        $password = (string) $this->db_password;

        config([
            'database.connections.mysql.host' => $host,
            'database.connections.mysql.port' => $port,
            'database.connections.mysql.database' => $database,
            'database.connections.mysql.username' => $username,
            'database.connections.mysql.password' => $password,
        ]);
        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            foreach ($this->requirements as $req) {
                if (!$req['status']) {
                    $this->addError('requirements', 'Please fix the requirements before proceeding.');
                    return;
                }
            }
        } elseif ($this->currentStep === 2) {
            foreach ($this->permissions as $perm) {
                if (!$perm['isSet']) {
                    $this->addError('permissions', 'Please fix the permissions before proceeding.');
                    return;
                }
            }
        } elseif ($this->currentStep === 3) {
            $this->validate($this->rules[3]);

            // Test Connection
            try {
                new PDO(
                    "mysql:host={$this->db_host};port={$this->db_port};dbname={$this->db_database}",
                    $this->db_username,
                    $this->db_password
                );
            } catch (\Exception $e) {
                $this->addError('connection', 'Could not connect to the database: ' . $e->getMessage());
                return;
            }

            // Apply config at runtime ONLY (do not write .env yet to avoid restart/reload)
            $this->configureDatabaseRuntime();

            // Run Migrations
            $this->isMigrating = true;
            try {
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $e) {
                $this->addError('migration', 'Migration failed: ' . $e->getMessage());
                $this->isMigrating = false;
                return;
            }
            $this->isMigrating = false;
        }

        $this->currentStep++;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    public function finish()
    {
        $this->validate($this->rules[4]);

        // Re-apply runtime config because this is a new request
        $this->configureDatabaseRuntime();

        try {
            User::create([
                'name' => $this->admin_name,
                'email' => $this->admin_email,
                'password' => Hash::make($this->admin_password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        } catch (\Exception $e) {
            $this->addError('admin', 'Failed to create admin: ' . $e->getMessage());
            return;
        }

        // NOW write .env (Safe to reload now as we are redirecting)
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);
            $replacements = [
                'DB_HOST' => $this->db_host,
                'DB_PORT' => $this->db_port,
                'DB_DATABASE' => $this->db_database,
                'DB_USERNAME' => $this->db_username,
                'DB_PASSWORD' => '"' . $this->db_password . '"',
                'APP_URL' => url('/'),
            ];

            foreach ($replacements as $key => $value) {
                if (preg_match('/^' . preg_quote($key) . '\s*=/m', $content)) {
                    $content = preg_replace('/^' . preg_quote($key) . '\s*=\s*(.*)$/m', $key . '=' . $value, $content);
                } else {
                    $content .= "\n" . $key . '=' . $value;
                }
            }
            file_put_contents($envPath, $content);
            Artisan::call('config:clear');
        }

        // Create installed file
        file_put_contents(storage_path('installed'), '');

        // Clear caches
        Artisan::call('optimize:clear');

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.installer')->layout('layouts.installer');
    }
}
