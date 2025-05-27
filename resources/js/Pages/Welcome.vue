<template>
  <Head title="Welcome" />
  <AppLayout>
    <div class="flex flex-col items-center space-y-4">
      <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
      <h1 class="text-4xl font-bold text-center">{{ $t('welcome_text') }}</h1>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>
