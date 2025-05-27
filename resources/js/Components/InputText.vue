<template>
  <InputLabel :for="id" :value="label"/>

  <TextInput
      :id="id"
      :type="type"
      class="mt-1 block w-full"
      v-model="modelValue"
      :required = "required"
      :autofocus = "autofocus"
      :autocomplete = "autocomplete"
      @input="$emit('update:modelValue', $event.target.value)"
  />

  <InputError class="mt-2" :message="message"/>
</template>

<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

defineProps({
  id: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: "text"
  },
  required: {
    type: Boolean,
    default: ""
  },
  autofocus: {
    type: String,
    default: ""
  },
  autocomplete: {
    type: String,
    default: ""
  },
  message: {
    type: String,
    default: null
  },
  label: {
    type: String,
    default: ""
  },
});

const modelValue = defineModel()

</script>