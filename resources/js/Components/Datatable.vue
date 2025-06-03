<template>
  <div class="bg-white rounded-lg overflow-hidden">
    
    
    <!-- Tabla (solo en pantallas md en adelante) -->
    <div class="hidden md:block overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
          <th
            v-for="(column,index) in columns"
            :key="index"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer select-none"
          >
            <template v-if="column.sortable">
              <div class="flex items-center px-2 py-1 rounded w-full max-w-sm">
                <div v-if="column.funnel" class="relative" @click="toggleDropdown(index)">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 mr-1 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                  </svg>
                  <!-- Menú desplegable -->
                  <div
                    v-if="dropdownOpen[index]"
                    class="absolute mt-2 w-80 bg-white border border-gray-200 rounded shadow-lg z-10"
                  >
                    <div class="flex justify-between px-4 py-2 border-b border-gray-200 text-sm text-gray-700">
                      <button @click.stop="applyFilter(index)" class="hover:underline">Filtrar</button>
                      <button @click.stop="clearFilter(index)" class="hover:underline">Limpiar</button>
                      <button @click.stop="selectAll(index)" class="hover:underline">Seleccionar todos</button>
                    </div>
                    <ul>
                      <li
                        v-for="option in funnels[index]"
                        :key="option.id"
                        @click="applyFilter(index, option.id)"
                        class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                      >
                        <input
                          type="checkbox"
                          :value="option.id"
                          v-model="selectedFilters[index]"
                          class="mr-2"
                        />
                        {{ option.label }}
                      </li>
                    </ul>
                  </div>
                </div>
                <input
                  type="text"
                  v-model="filters[index].value"
                  @input="debouncedSearch"
                  class="px-2 py-1 rounded text-sm border-none w-full"
                  :placeholder="column.label"
                >
                <template v-if="column.sortable">
                  <div @click="sortBy(column)" class="cursor-pointer justify-end w-10 ">
                    <svg v-if="filters[index].sort === 'asc'" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18"/>
                    </svg>
                    
                    <svg v-else-if="filters[index].sort === 'desc'" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                    </svg>
                    
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                      <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5"/>
                    </svg>
                  
                  </div>
                </template>
              </div>
            </template>
            <template v-else>
              {{ column.label }}
            </template>
          
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
            v-for="(column,index) in columns"
            :key="index"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
            @click="goShow(item.id)"
          >
            {{ item[index] }}
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
          <span class="ml-1 text-gray-900">{{ column }}</span>
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
import {Link} from '@inertiajs/vue3';
import {computed, reactive, ref} from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
  columns: Object,
  filters: Object,
  funnels: Object,
  pagination: Object,
  go_show: {
    type: Boolean,
    default: true
  },
  rowActions: {
    type: Array,
    default: () => [],
  },
  search: {
    type: String,
    default: '',
  },
});

const dropdownOpen = ref({})

const filteredData = computed(() => {
  return pagination.data.filter(item => {
    return columns.value.every(column => {
      const filterValue = column.filter.value.toLowerCase();
      const itemValue = String(resolveField(item, column.key)).toLowerCase();
      return itemValue.includes(filterValue);
    });
  });
});

const emits = defineEmits(['goShow', 'changeFilters'])

const search = ref(props.search || '');
const selectedFilters = reactive({});
function toggleDropdown(index) {
  dropdownOpen.value[index] = !dropdownOpen.value[index];
}

Array.from(props.columns).forEach((_, index) => {
  selectedFilters[index] = [];
});

function formatLabel(label) {
  if (label === 'pagination.previous') return '&laquo;';
  if (label === 'pagination.next') return '&raquo;';
  return label;
}

function sortBy(header) {
  let direction = header.filter.sort
  props.headers.filters.forEach((filter) => {
    if (filter.key !== header.key) {
      filter.sort = ''
    }
  })
  header.filter.sort = direction === 'asc' ? 'desc' : direction === 'desc' ? '' : 'asc';
  reload();
}

const debouncedSearch = debounce(() => {
  reload();
}, 500);

function reload() {
  emits('changeFilters', {search: search.value, filters: props.filters})
}

const goShow = (id) => {
  if (props.go_show) {
    emits('goShow', id)
  }
}

function applyFilter(index, option) {
  props.filters[index].value = [...selectedFilters[index]];
  debouncedSearch();
  dropdownOpen.value[index] = false;
}

function clearAll(index) {
  selectedFilters[index] = [];
}

function selectAll(index) {
  selectedFilters[index] = props.funnels[index].map(option => option.id);
}
</script>
