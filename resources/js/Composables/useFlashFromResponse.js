// composables/useFlashFromResponse.js
import { nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification'

export function useFlashFromResponse() {
  const toast = useToast()
  const page = usePage()

  function showFlash() {
    nextTick(() => {
      const flash = page.props.flash
      if (flash?.success) toast.success(flash.success)
      if (flash?.error) toast.error(flash.error)
    })
  }

  return { showFlash }
}
