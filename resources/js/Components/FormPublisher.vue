<template>
  <div class="space-y-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">{{ $t('name') }}</label>
      <input
        v-model="form.name"
        type="text"
        :placeholder="$t('name')"
        class="mt-1 w-full border rounded px-3 py-2 text-sm"
        :class="{ 'border-red-500': form.errors.name }"
      />
      <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
    </div>

  </div>
</template>

<script setup>
const props = defineProps({
  form: Object,
});
</script>
