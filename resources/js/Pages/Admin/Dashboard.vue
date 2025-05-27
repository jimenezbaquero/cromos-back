<template>
  <Head title="Dashboard"/>

  <AdminLayout>
    <template #header>
      <h2
          class="text-xl font-semibold leading-tight text-gray-800"
      >
        Dashboard
      </h2>
    </template>

    <div class="p-6">
      <div class="bg-white shadow-sm sm:rounded-lg w-full">
        <div class="text-gray-900">
          You're logged in!
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Head} from '@inertiajs/vue3';
</script>