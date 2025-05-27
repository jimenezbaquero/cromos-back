<template>
  <Head title="Usuarios"/>
  
  <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
    </svg>
  </div>
  
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('users') }}
    </h2>
    
    <div class="py-6">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end">
          <button
            @click="showModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded"
          >
            + {{ $t('create') }}
          </button>
        </div>
        <div class="bg-white rounded-lg p-6 pt-0">
          <Datatable :columns="columns" :pagination="users"/>
        </div>
      </div>
    </div>
    
    <CreateUserModal
      :show="showModal"
      :roles="roles"
      @close="showModal = false"
      @submitted="handleUserCreated"
    />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Datatable from '@/Components/Datatable.vue';
import CreateUserModal from '@/Components/CreateUserModal.vue';
import {computed, ref} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {useI18n} from "vue-i18n";

const props = defineProps({
  users: Object,
  roles: Array
});

const {t} = useI18n()

const showModal = ref(false);
const isLoading = ref(false);


const columns = computed(() =>[
  {key: 'id', label: 'ID', sortable: true},
  {key: 'name', label: t('name'), sortable: true},
  {key: 'email', label: t('email'), sortable: true},
  {key: 'role', label: t('role'), sortable: false},
  {key: 'created_at', label: t('created_at'), sortable: true}
]);

const handleUserCreated = () => {
  showModal.value = false;
  Inertia.reload()
};
</script>
