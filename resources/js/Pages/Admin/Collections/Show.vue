<template>
  <Head :title="$t('collection_show')"/>
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('collection_show') }}
    </h2>
    
    <div class="space-y-2 text-sm">
      <p><strong>{{ $t('name') }}:</strong> {{ collection.name }}</p>
      <p><strong>{{ $t('description') }}:</strong> {{ collection.description }}</p>
      <p><strong>{{ $t('year') }}:</strong> {{ collection.year }}</p>
      <p><strong>{{ $t('publisher') }}:</strong> {{ collection.publisher }}</p>
    </div>
    
  </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  collection: Object,
});
</script>
