<template>
  <Head :title="$t('publishers')" />
  
  <div v-if="isLoading" class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
    </svg>
  </div>
  
  <AdminLayout>
    <h2 class="text-xl">
      {{ $t('publishers') }}
    </h2>
    
    <div class="py-6">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end">
          <Link
            :href="route('admin.publishers.create')"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded"
          >
            + {{ $t('create') }}
          </Link>
        </div>
        
        <div class="bg-white rounded-lg pt-0">
          <Datatable
            :columns="headers"
            :pagination="filteredData"
            :filters="filters"
            :row-actions="rowActions"
            :go_show="true"
            @goShow = "goShow"
            @changeFilters = "changeFilters"
          />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Datatable from '@/Components/Datatable.vue'
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Head, Link, router } from '@inertiajs/vue3'
import { PencilSquareIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
  publishers: Object,
  filters: Object,
  headers: Object
})

const { t } = useI18n()
const isLoading = ref(false)

const filteredData = ref(props.publishers)

const rowActions = [
  {
    label: 'edit',
    icon: PencilSquareIcon,
    onClick: (item) => router.visit(route('admin.publishers.edit', item.id)),
    class: 'text-blue-600 hover:text-blue-800'
  }
]

function goShow(id){
  router.visit(route('admin.publishers.show', id))
}

const changeFilters = (filters) => {
  isLoading.value = true
  axios.post(route('admin.publishers.getData'),filters)
    .then((response) => {
      filteredData.value = response.data
      isLoading.value = false
    })
}
</script>
