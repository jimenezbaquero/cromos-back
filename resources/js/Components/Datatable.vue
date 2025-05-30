<template>
  <div class="bg-white rounded-lg overflow-hidden">
    <!-- Filtro -->
    <div class="py-4 flex justify-between items-center">
      <input
        type="text"
        v-model="search"
        @input="debouncedSearch"
        :placeholder="$t('search')+'...'"
        class="border px-4 py-2 rounded w-full max-w-sm text-sm"
      />
    </div>
    
    <!-- Tabla (solo en pantallas md en adelante) -->
    <div class="hidden md:block overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
          <th
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none"
            @click="column.sortable? sortBy(column.key) : ''"
          >
            {{ column.label }}
            <span v-if="sort.field === column.key">
                {{ sort.direction === 'asc' ? '⬆' : sort.direction === 'desc' ? '⬇' : '' }}
              </span>
          </th>
          <th v-if="rowActions?.length"
              class="px-6 py-3 text-left text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
            {{ $t('actions') }}
          </th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <tr v-for="item in pagination.data" :key="item.id" class="hover:bg-gray-200">
          <td
            v-for="column in columns"
            :key="column.key"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
            @click="goShow(item.id)"
          >
            {{ resolveField(item, column.key) }}
          </td>
          <td v-if="rowActions?.length"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 space-x-2 flex justify-around">
            <template v-for="(action, i) in rowActions" :key="i">
              <component
                v-if="action.href"
                :is="action.component || 'a'"
                :href="action.href(item)"
                :class="action.class"
                :title="$t(action.label)"
                class="flex items-center space-x-1"
              >
                <component :is="action.icon" class="w-4 h-4"/>
              </component>
              <button
                v-else
                @click="() => action.onClick(item)"
                :class="action.class"
                :title="$t(action.label)"
                class="flex items-center space-x-1"
              >
                <component :is="action.icon" class="w-4 h-4"/>
              </button>
            </template>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    
    <!-- Tarjetas (solo en pantallas pequeñas) -->
    <div class="block md:hidden">
      <div
        v-for="item in pagination.data"
        :key="item.id"
        class="border rounded-lg p-2 shadow-sm mt-2"
      >
        <div class="flex justify-end">
          <template v-for="(action, i) in rowActions" :key="i">
            <button
              @click="() => action.onClick(item)"
              :class="action.class"
              class="flex mr-2 items-center space-x-1"
            >
              <component :is="action.icon" class="w-4 h-4"/>
            </button>
          </template>
        </div>
        <div
          v-for="column in columns"
          :key="column.key"
          class="mb-1 text-sm"
        >
          <span class="font-semibold text-gray-600 ">{{ column.label }}:</span>
          <span class="ml-1 text-gray-900">{{ resolveField(item, column.key) }}</span>
        </div>
      </div>
    </div>
    
    <!-- Paginación -->
    <div class="flex justify-end items-center p-4 space-x-2 text-sm">
      <Component
        v-for="(link, index) in pagination.links"
        :key="index"
        :is="link.url ? Link : 'span'"
        :href="link.url"
        class="px-3 py-1 rounded border"
        :class="{
          'bg-blue-500 text-white': link.active,
          'text-gray-700 hover:bg-gray-100': !link.active && link.url,
          'text-gray-400': !link.url,
        }"
        v-html="formatLabel(link.label)"
        preserve-scroll
      />
    </div>
  </div>
</template>

<script setup>
import {Link, router} from '@inertiajs/vue3';
import {ref} from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  columns: Array,
  pagination: Object,
  go_show: {
    type: Boolean,
    default: true
  },
  rowActions: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({
      search: '',
      sort: '',
      direction: '',
    }),
  },
});

const emits = defineEmits(['goShow'])

const search = ref(props.filters.search || '');
const sort = ref({
  field: props.filters.sort || '',
  direction: props.filters.direction || '',
});

function resolveField(item, key) {
  return key.split('.').reduce((obj, prop) => {
    if (Array.isArray(obj) && !isNaN(prop)) {
      return obj[parseInt(prop)] ?? '';
    }
    return obj?.[prop] ?? '';
  }, item);
}

function formatLabel(label) {
  if (label === 'pagination.previous') return '&laquo;';
  if (label === 'pagination.next') return '&raquo;';
  return label;
}

function sortBy(field) {
  if (sort.value.field === field) {
    sort.value.direction = sort.value.direction === 'asc' ? 'desc' : sort.value.direction === 'desc' ? '' : 'asc';
  } else {
    sort.value.field = field;
    sort.value.direction = 'asc';
  }
  reload();
}

const debouncedSearch = debounce(() => {
  reload();
}, 500);

function reload() {
  router.get(
    window.location.pathname,
    {
      search: search.value,
      sort: sort.value.field,
      direction: sort.value.direction,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
}

const goShow = (id) =>{
  if(props.go_show) {
    emits('goShow', id)
  }
}
</script>
