<template>
  <Head :title="$t('users')" />

  <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
    </svg>
  </div>

  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('users') }}
    </h2>

    <div class="py-6">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end">
          <Link
            :href="route('admin.users.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded"
          >
            + {{ $t('create') }}
          </Link>
        </div>

        <div class="bg-white rounded-lg pt-0">
          <Datatable
            :columns="headers"
            :pagination="filteredData"
            :row-actions="rowActions"
            :filters="filters"
            :funnels="funnels"
            :go_show="true"
            @goShow = "goShow"
            @changeFilters = "changeFilters"
          />
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de borrado -->
    <ConfirmModal
      :show="showConfirmModal"
      :title="$t('delete_user')"
      :message="$t('sure_to') + ' ' + selectedUser?.name + '? ' + $t('cant_undone')"
      @cancelAction="cancelDelete"
      @confirmAction="performDelete"
    />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Datatable from '@/Components/Datatable.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Head, Link, router } from '@inertiajs/vue3'
import { useFlashFromResponse } from '@/Composables/useFlashFromResponse'
import { PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  users: Object,
  roles: Array,
  headers: Object,
  filters: Object,
  funnels: Object
})

const { t } = useI18n()
const { showFlash } = useFlashFromResponse()

const showConfirmModal = ref(false)
const isLoading = ref(false)
const selectedUser = ref(null)
const filteredData = ref(props.users)

const columns = computed(() => [
  { key: 'id', label: 'ID', sortable: true, filter: { sort: '', value: '', type: 'text' } },
  { key: 'name', label: t('name'), sortable: true, filter: { sort: '', value: '', type: 'text' } },
  { key: 'email', label: t('email'), sortable: true, filter: { sort: '', value: '', type: 'text' } },
  { key: 'role', label: t('role'), sortable: false, filter: { sort: '', value: '', type: 'select', options: [] } },
  { key: 'created_at', label: t('created_at'), sortable: true, filter: { sort: '', value: '', type: 'date' } }
])

const rowActions = [
  {
    label: 'edit',
    icon: PencilSquareIcon,
    onClick: (item) => router.visit(route('admin.users.edit', item.id)),
    class: 'text-blue-600 hover:text-blue-800'
  },
  {
    label: 'show',
    icon: EyeIcon,
    onClick: (item) => router.visit(route('admin.users.show', item.id)),
    class: 'text-yellow-600 hover:text-yellow-800'
  },
  {
    label: 'delete',
    icon: TrashIcon,
    onClick: (item) => confirmDelete(item),
    class: 'text-red-600 hover:text-red-800'
  }
]

function confirmDelete(user) {
  selectedUser.value = user
  showConfirmModal.value = true
}

function performDelete() {
  isLoading.value = true
  if (selectedUser.value) {
    router.delete(route('admin.users.destroy', selectedUser.value.id), {
      preserveScroll: true,
      onSuccess: () => {
        cancelDelete()
        isLoading.value = false
        showFlash()
      },
      onError: () => {
        isLoading.value = false
        showFlash()
      }
    })
  }
}

function cancelDelete() {
  showConfirmModal.value = false
  selectedUser.value = null
}

function goShow(id){
  router.visit(route('admin.users.show', id))
}

const changeFilters = (filters) => {
  isLoading.value = true
  axios.post(route('admin.users.getData'),filters)
    .then((response) => {
      filteredData.value = response.data
      isLoading.value = false
    })
}
</script>
