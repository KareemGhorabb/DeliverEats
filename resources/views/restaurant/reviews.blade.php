@extends('layouts.restaurant')
@section('page-title', 'Reviews')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="bg-white rounded-2xl border border-surface-200/50 px-6 py-4 text-center">
            <p class="text-3xl font-display font-bold text-surface-900">4.8</p>
            <div class="flex items-center justify-center gap-0.5 mt-1 text-amber-400">@for($i=0;$i<5;$i++)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
            <p class="text-xs text-surface-300 mt-1">324 reviews</p>
        </div>
        <div class="space-y-1.5">
            @foreach([['5', 68], ['4', 22], ['3', 7], ['2', 2], ['1', 1]] as $bar)
            <div class="flex items-center gap-2"><span class="text-xs text-surface-300 w-3">{{ $bar[0] }}</span><div class="w-32 h-2 bg-surface-100 rounded-full overflow-hidden"><div class="h-full bg-amber-400 rounded-full" style="width:{{ $bar[1] }}%"></div></div><span class="text-[10px] text-surface-300 w-8">{{ $bar[1] }}%</span></div>
            @endforeach
        </div>
    </div>
</div>
<div class="space-y-4">
    @php $reviews = [
        ['name' => 'Ahmed H.', 'rating' => 5, 'date' => '2 hours ago', 'comment' => 'Best shawarma in the area! Always fresh, always on time. The garlic sauce is incredible.', 'order' => '2× Chicken Shawarma, 1× Fries'],
        ['name' => 'Sara M.', 'rating' => 4, 'date' => 'Yesterday', 'comment' => 'Great food but the delivery took a bit longer than expected. The Mixed Grill was perfect though.', 'order' => '1× Mixed Grill Platter'],
        ['name' => 'Nour A.', 'rating' => 5, 'date' => '3 days ago', 'comment' => 'Absolutely love this place. The falafel wrap is my go-to lunch. Packaging was neat and food was still hot.', 'order' => '2× Falafel Wrap, 1× Lemonade'],
        ['name' => 'Omar K.', 'rating' => 5, 'date' => '1 week ago', 'comment' => 'Ordered the kebab platter for a family dinner. Portions were generous and everything tasted authentic.', 'order' => '3× Kebab Platter, 2× Hummus'],
    ]; @endphp
    @foreach($reviews as $r)
    <div class="bg-white rounded-2xl border border-surface-200/50 p-5">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-sm font-bold">{{ substr($r['name'],0,1) }}</div>
                <div>
                    <p class="text-sm font-semibold">{{ $r['name'] }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <div class="flex text-amber-400">@for($i=0;$i<$r['rating'];$i++)<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                        <span class="text-xs text-surface-300">{{ $r['date'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-sm text-surface-800/70 mt-3 leading-relaxed">{{ $r['comment'] }}</p>
        <p class="text-xs text-surface-300 mt-2">Order: {{ $r['order'] }}</p>
    </div>
    @endforeach
</div>
@endsection
