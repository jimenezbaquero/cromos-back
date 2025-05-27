<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
          {{$t('forgot_password_text')}}
        </div>

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

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                  class = "bg-green-600"
                  :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                  {{ $t('forgot_password_button') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputText from "@/Components/InputText.vue";
import {useFlashFromResponse} from "@/Composables/useFlashFromResponse.js";

const form = useForm({
  email: '',
});

const {showFlash} = useFlashFromResponse()

const submit = () => {
  form.post(route('password.email'), {
    onSuccess: () => {

    },
    onError: () => {

    },
    onFinish: () => {
      showFlash()
    }
  });
};
</script>
