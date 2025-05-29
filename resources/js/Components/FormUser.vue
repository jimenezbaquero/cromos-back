<template>
  <div class="flex flex-col px-8 mt-8 bg-white space-y-4">
    <div>
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
    
    <div>
      <label for="name" class="block font-medium text-gray-700 mb-1">{{$t('email')}}</label>
      <input
        id="email"
        type="email"
        v-model="form.email"
        class="w-full border border-gray-300 rounded px-3 py-2"
        :class="{'border-red-500': form.errors.email}"
      />
      <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
    </div>
    
    <div>
      <label for="name" class="block font-medium text-gray-700 mb-1">{{$t('role')}}</label>
      <select
        v-model="form.role"
        class="w-full border border-gray-300 rounded px-3 py-2"
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
