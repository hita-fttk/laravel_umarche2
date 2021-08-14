<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            期限切れオーナー一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    
                    
                    <section class="text-gray-600 body-font">
  <div class="container px-5 mx-auto">
      <x-flash-message status="session.('status')" />

    <div class="lg:w-2/3 w-full mx-auto overflow-auto">
      <table class="table-auto w-full text-left whitespace-no-wrap">
        <thead>
          <tr>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">名前</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">メールアドレス</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">期限が切れた日</th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100"></th>
            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tr rounded-br"></th>
          </tr>
        </thead>
        <tbody>
            @foreach ($expiredOweners as $owener)
          <tr>
            <td class="px-4 py-3">{{ $owener->name }}</td>
            <td class="px-4 py-3">{{ $owener->email }}</td>
            <td class="px-4 py-3">{{ $owener->deleted_at->diffForHumans() }}</td>

            <form method="post" id="delete_{{$owener->id}}" action="{{ route('admin.expired-oweners.destroy', ['owener'=> $owener->id]) }}">
                @csrf
            <td class="px-4 py-3">
              <a href="#" data-id="{{ $owener->id }}" onclick="deletePost(this)" class="text-white bg-red-400 border-0 py-2 px-6 focus:outline-none hover:bg-red-600 rounded ">完全に削除する</a>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
    <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
      <a class="text-purple-500 inline-flex items-center md:mb-2 lg:mb-0">Learn More
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
          <path d="M5 12h14M12 5l7 7-7 7"></path>
        </svg>
      </a>
      
    </div>
  </div>
</section>
                    {{--  エロくアント
                    @foreach($e_all as $e_owener)
                    {{ $e_owener->name }}
                    {{ $e_owener->created_at->diffForHumans() }}
                    @endforeach
                    <br>
                    クエリビルダ
                    @foreach($q_get as $q_owener)
                    {{ $q_owener->name }}
                    {{ Carbon\Carbon::parse($q_owener->created_at)->diffForHumans() }}
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>
    <script>
        function deletePost(e) {
            'use strict';
            if (confirm('本当に削除してもいいですか！？')){
            document.getElementById('delete_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
