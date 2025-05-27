<template>
  <Head title="Log in"/>
  <GuestLayout>
    <div class="mt-6 space-y-3">
      <a
        :href="route('social.redirect', { provider: 'google' })"
        class="block w-full text-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded"
      >
        {{ $t('login_google') }}
      </a>
      <a
        :href="route('social.redirect', { provider: 'facebook' })"
        class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
      >
        {{ $t('login_facebook') }}
      </a>
    </div>
    
    <hr class="my-6"/>
    
    <form @submit.prevent="submit">
      <div>
        <InputText
          :label="$t('email')"
          id="email"
          type="email"
          v-model="form.email"
          required="required"
          autofocus="autofocus"
          autocomplete="username"
          :message="form.errors.email"
        />
      </div>
      
      <div class="mt-4">
        <InputText
          :label="$t('password')"
          id="password"
          type="password"
          v-model="form.password"
          required="required"
          autocomplete="current-password"
          :message="form.errors.password"
        />
      </div>
      
      <div class="mt-4 block">
        <label class="flex items-center">
          <Checkbox name="remember" v-model:checked="form.remember"/>
          <span class="ms-2 text-sm text-gray-600"
          >{{ $t('remember') }}</span
          >
        </label>
      </div>
      
      
      <div class="mt-4 flex items-center justify-end">
        <Link
          v-if="canResetPassword"
          :href="route('password.request')"
          class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          {{ $t('forgot_password') }}
        </Link>
        
        <PrimaryButton
          class="ms-4 bg-green-600"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('login') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {useFlashFromResponse} from '@/Composables/useFlashFromResponse';
import {onMounted} from "vue";
import InputText from "@/Components/InputText.vue";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  canLogin: {
    type: Boolean,
    default: true
  },
  canRegister: {
    type: Boolean,
    default: true
  },
});

const {showFlash} = useFlashFromResponse()

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};

onMounted(() => {
  showFlash()
})
</script>
