@extends('layouts.app')

@section('title', 'T-THRIFT - Customer Storefront')

@section('content')
<section class="space-y-12">
  <div class="relative rounded-3xl overflow-hidden bg-[#CBD5E1] min-h-[380px] sm:min-h-[440px] flex items-end p-8 sm:p-14 shadow-sm border border-zinc-300/50">
    <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?auto=format&fit=crop&w=1600&q=80" alt="Hero" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-40 filter contrast-125"/>
    <div class="relative z-10 max-w-xl">
      <span class="inline-block px-3 py-1 bg-zinc-900 text-white text-xs font-black tracking-widest uppercase rounded-full mb-3">Sustainable Fashion</span>
      <h1 class="text-3xl sm:text-5xl font-black text-zinc-900 tracking-tight leading-tight mb-4">FIND YOUR SIGNATURE VINTAGE PIECE.</h1>
      <a href="" class="inline-block bg-[#71717A] hover:bg-[#52525B] text-white font-extrabold text-base tracking-wider px-8 py-3.5 rounded-2xl shadow-lg transition uppercase">
        ORDER NOW !
      </a>
    </div>
  </div>

  <div class="py-6 text-center max-w-3xl mx-auto px-4">
    <h2 class="text-3xl sm:text-4xl font-black text-zinc-900 mb-4 tracking-tight">Hello, We Are T-Thrift</h2>
    <p class="text-base sm:text-lg text-zinc-600 leading-relaxed">TahtaThrift is your destination for quality preloved apparel combining style, affordability, and sustainability.</p>
  </div>

  <div>
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl sm:text-2xl font-black tracking-tight text-zinc-900">Featured Curation</h3>
      <a href="" class="text-sm font-bold text-zinc-600 hover:text-black flex items-center gap-1">Explore All →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
      @foreach ($products as $p)
        <div class="group flex flex-col space-y-3 cursor-pointer product-card" 
            data-id="{{ $p->id }}"
            data-name="{{ $p->name }}"
            data-price="{{ $p->price }}"
            data-image="{{ asset($p->image_url) }}"
            data-condition="{{ $p->condition_rating }}"
            data-brand="{{ $p->brand }}"
            data-size="{{ $p->size }}"
            data-dimension="{{ $p->length . ' x ' . $p->width }}"
            data-material="{{ $p->material }}"
            data-defect="{{ $p->defect_notes }}"
            data-description="{{ $p->description }}"
            onclick="openProductDetailModal(this)">
            
          <div class="relative aspect-[4/5] bg-[#CBD5E1] rounded-3xl overflow-hidden">
            <img src="{{ asset($p->image_url) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
          </div>
          <div>
            <h4 class="font-bold text-base">{{ $p->name }}</h4>
            <p class="font-extrabold text-sm">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endsection