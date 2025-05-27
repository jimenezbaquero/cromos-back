<template>
  <Head title="Welcome" />
  <h1 class="text-4xl font-bold text-center">{{$t('dashboard de cliente')}}</h1>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
defineOptions({
  layout: AdminLayout,
})
</script>
