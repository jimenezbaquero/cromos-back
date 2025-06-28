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
        @change="changeRole"
      >
        <option disabled value="">{{ $t('select_an_option') }}</option>
        <option v-for="role in roles" :key="role.id" :value="role.name">
          {{ role.name }}
        </option>
      </select>
      <p v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</p>
    </div>

    <template v-if="showPublishers">
      <div>
        <label for="name" class="block font-medium text-gray-700 mb-1">{{$t('publisher')}}</label>
        <select
            v-model="form.publisher_id"
            class="w-full border border-gray-300 rounded px-3 py-2"
            :class="{ 'border-red-500': form.errors.publisher_id }"
        >
          <option disabled value="">{{ $t('select_an_option') }}</option>
          <option v-for="publisher in publishers" :key="publisher.id" :value="publisher.id">
            {{ publisher.name }}
          </option>
        </select>
        <p v-if="form.errors.publisher_id" class="text-red-500 text-sm mt-1">{{ form.errors.publisher_id }}</p>
      </div>
    </template>
  </div>
</template>

<script setup>
import {computed, ref, watch} from "vue";

const props = defineProps({
  roles: Array,
  form: Object,
  publishers: Array
});

const showPublishers = computed(() => props.form.role === 'editor')

watch(() => props.form.role, (newRole) => {
  if (newRole !== 'editor') {
    props.form.publisher_id = ''
  }
})
</script>
