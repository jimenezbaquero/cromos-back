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
    
    <div class="mb-4">
      <label for="description" class="block font-medium text-gray-700 mb-1">{{$t('description')}}</label>
      <textarea
        id="description"
        rows="4"
        v-model="form.description"
        class="w-full border border-gray-300 rounded px-3 py-2"
        :class="{'border-red-500': form.errors.description}"
      ></textarea>
      <p v-if="form.errors.description" class="text-red-600 text-sm mt-1">{{ form.errors.description }}</p>
    </div>
    
    <div class="mb-4">
      <label for="year" class="block font-medium text-gray-700 mb-1">{{$t('year')}}</label>
      <input
        id="year"
        type="number"
        min="1900"
        max="2100"
        v-model="form.year"
        class="w-full border border-gray-300 rounded px-3 py-2"
        :class="{'border-red-500': form.errors.year}"
      />
      <p v-if="form.errors.year" class="text-red-600 text-sm mt-1">{{ form.errors.year }}</p>
    </div>
    
    <div class="mb-4">
      <label for="publisher_id" class="block font-medium text-gray-700 mb-1">{{$t('publisher')}}</label>
      <select
        id="publisher_id"
        v-model="form.publisher_id"
        class="w-full border border-gray-300 rounded px-3 py-2"
        :class="{'border-red-500': form.errors.publisher_id}"
      >
        <option value="" disabled>{{ $t('select_an_option') }}</option>
        <option
          v-for="publisher in publishers"
          :key="publisher.id"
          :value="publisher.id"
        >
          {{ publisher.name }}
        </option>
      </select>
      <p v-if="form.errors.publisher" class="text-red-600 text-sm mt-1">{{ form.errors.publisher }}</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  publishers: {
    type: Array,
    default: () => [],
  },
});

</script>
