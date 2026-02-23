<?php $date = \Carbon\Carbon::yesterday()->format('Y-m-d'); ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{$date}}</lastmod>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ url('raffles') }}</loc>
        <lastmod>{{$date}}</lastmod>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ url('terms') }}</loc>
        <lastmod>{{$date}}</lastmod>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ url('my-tickets') }}</loc>
        <lastmod>{{$date}}</lastmod>
        <priority>0.8</priority>
    </url>

    @foreach (\App\Models\Raffle::where('status', 'active')->get() as $raffle)
    <url>
        <loc>{{ $raffle->getUrl() }}</loc>
        <lastmod>{{$date}}</lastmod>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>