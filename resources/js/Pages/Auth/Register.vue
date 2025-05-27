<template>
  <GuestLayout>
    <Head title="Register"/>
    <div class="mt-6 space-y-3">
      <a
          :href="route('social.redirect', { provider: 'google' })"
          class="block w-full text-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded"
      >
        {{ $t('register_google') }}
      </a>
      <a
          :href="route('social.redirect', { provider: 'facebook' })"
          class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
      >
        {{ $t('register_facebook') }}
      </a>
    </div>
    <hr class="my-6" />
    <form @submit.prevent="submit">
      <div>
        <InputText
            :label = "$t('name')"
            id="name"
            type = "text"
            v-model ="form.name"
            required = "required"
            autofocus = "autofocus"
            autocomplete = "name"
            :message="form.errors.name"
        />
      </div>

      <div class="mt-4">
        <InputText
            :label = "$t('email')"
            id="email"
            type = "email"
            v-model ="form.email"
            required = "required"
            autocomplete = "username"
            :message="form.errors.email"
        />
      </div>

      <div class="mt-4">
        <InputText
            :label = "$t('password')"
            id="password"
            type = "password"
            v-model ="form.password"
            required = "required"
            autocomplete = "new-password"
            :message="form.errors.password"
        />
      </div>

      <div class="mt-4">
        <InputText
            :label = "$t('confirm_password')"
            id="password_confirmation"
            type = "password"
            v-model ="form.password_confirmation"
            required = "required"
            autocomplete = "current-password"
            :message="form.errors.password_confirmation"
        />
      </div>


      <div class="mt-4 flex items-center justify-end">
        <Link
            :href="route('login')"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
          {{ $t('already_registered')}}
        </Link>

        <PrimaryButton
            class="ms-4 bg-green-600"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
        >
          {{ $t('register') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import InputText from "@/Components/InputText.vue";

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>
