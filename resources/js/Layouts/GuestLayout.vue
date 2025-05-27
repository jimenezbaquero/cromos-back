<template>
  <div class="min-h-screen flex flex-col">
    <AppHeader :canLogin="$page.props.canLogin" :canRegister="$page.props.canRegister" />
    
    <main class="flex-grow flex flex-col items-center bg-gray-100 pt-6 sm:pt-0 justify-center">
      <div>
        <Link href="/">
          <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
        </Link>
      </div>
      
      <div
        class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg flex flex-col"
      >
        <slot />
      </div>

    </main>
    
    <AppFooter />
  </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
import AppHeader from "@/Components/Header.vue";
import AppFooter from "@/Components/Footer.vue"
</script>