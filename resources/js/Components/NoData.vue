<template>
  <div class="flex flex-col items-center justify-center w-full text-center text-gray-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24"
         stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9.75 17L3 10.25l1.41-1.41L9.75 14.17l10.59-10.59L21.75 5.25 9.75 17z"/>
    </svg>
    <p class="text-lg font-semibold">{{$t('no_data_text1')}}</p>
    <p class="text-sm text-gray-400">{{$t('no_data_text2')}}</p>
  </div>
</template>
