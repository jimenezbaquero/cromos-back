<template>
  <Head :title="$t('collections')" />
  
  <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
    </svg>
  </div>
  
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('create_collection') }}
    </h2>
    
    <FormCollection
      v-model:form="form"
      :publishers="publishers"
      class = "mt-4"
    />
    
    <div class="mt-8 mr-8 flex justify-end">
      <button
        @click="save"
        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 text-sm"
      >
        {{ $t('save') }}
      </button>
    </div>
  </AdminLayout>
</template>

<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {ref} from "vue"; //
import { useFlashFromResponse } from '@/Composables/useFlashFromResponse'
import FormCollection from "@/Components/FormCollection.vue";

const { showFlash } = useFlashFromResponse()

const props = defineProps({
  show: Boolean,
  publishers: Array,
});

const isLoading = ref(false)

const form = useForm({
  name: '',
  description: '',
  year: '',
  photo: null,
  publisher_id:''
});

const save = () => {
  form.post(route('admin.collections.store'), {
    onSuccess: () => {
      showFlash()
    },
    onError: () => {
      showFlash()
    },
  })
}

</script>
