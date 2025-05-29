<template>
  <div class="flex flex-col p-8 bg-white">
    <div class="mb-4">
      <label for="name" class="block font-medium text-gray-700 mb-1">{{$t('name')}}</label>
      <input
        id="name"
        type="text"
        v-model="form.name"
        class="w-full border border-gray-300 rounded px-3 py-2"
        :class="{'border-red-500': form.errors.name}"
      />
      <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
    </div>

  </div>
</template>

<script setup>
const props = defineProps({
  form: Object,
});
</script>
