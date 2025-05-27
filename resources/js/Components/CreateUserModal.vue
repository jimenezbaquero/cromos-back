<template>
  <transition name="fade">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
      <div class="bg-white rounded-lg p-6 w-full max-w-md relative">
        <h3 class="text-lg font-semibold mb-4">Nuevo Usuario</h3>

        <div class="space-y-4">
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{$t('name')}}</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              :placeholder="$t('name')"
              class="mt-1 w-full border rounded px-3 py-2 text-sm"
              :class="{ 'border-red-500': form.errors.name }"
            />
            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              :placeholder="$t('email')"
              class="mt-1 w-full border rounded px-3 py-2 text-sm"
              :class="{ 'border-red-500': form.errors.email }"
            />
            <p v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</p>
          </div>

          <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
            <select
              id="role"
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

        <div class="mt-6 flex justify-end space-x-2">
          <button
            @click="cancel"
            class="px-4 py-2 text-sm text-gray-600 hover:underline"
          >
            {{$t('cancel')}}
          </button>
          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            @click="save"
          >
            <span>{{$t('save')}}</span>
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { useFlashFromResponse } from '@/Composables/useFlashFromResponse';

const props = defineProps({
  show: Boolean,
  roles: Array
});

const emit = defineEmits(['close', 'submitted']);

const { showFlash } = useFlashFromResponse();

const form = useForm({
  name: '',
  email: '',
  role: ''
});

const save = () => {
  form.post(route('users.store'), {
    onSuccess: () => {
      form.reset();
      emit('submitted');
      showFlash();
    },
    onError: () => {
      showFlash();
    }
  });
};

const cancel = () => {
  form.reset();
  emit('close');
};
</script>
