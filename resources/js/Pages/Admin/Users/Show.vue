<template>
  <Head :title="$t('user_show')"/>
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('user_show') }}
    </h2>
    
    <div class="space-y-2 text-sm">
      <p><strong>{{ $t('name') }}:</strong> {{ user.name }}</p>
      <p><strong>{{ $t('email') }}:</strong> {{ user.email }}</p>
      <p><strong>{{ $t('role') }}:</strong> {{ user.role }}</p>
    </div>
    
  </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  user: Object,
});
</script>
