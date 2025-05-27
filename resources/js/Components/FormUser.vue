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
    
    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input
        v-model="form.email"
        type="email"
        :placeholder="$t('email')"
        class="mt-1 w-full border rounded px-3 py-2 text-sm"
        :class="{ 'border-red-500': form.errors.email }"
      />
      <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
    </div>
    
    <div>
      <label class="block text-sm font-medium text-gray-700">{{ $t('role') }}</label>
      <select
        v-model="form.role"
        class="mt-1 w-full border rounded px-3 py-2 text-sm"
        :class="{ 'border-red-500': form.errors.role }"
      >
        <option disabled value="">{{ $t('select_an_option') }}</option>
        <option v-for="role in roles" :key="role.id" :value="role.name">
          {{ role.name }}
        </option>
      </select>
      <p v-if="form.errors.role" class="text-red-500 text-xs mt-1">{{ form.errors.role }}</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  roles: Array,
  form: Object,
});
</script>
