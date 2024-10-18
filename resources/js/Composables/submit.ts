import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { VisitOptions } from '@inertiajs/core'

export function useSubmit(href: string, options: VisitOptions) {
  const loading = ref<boolean>(false)
  const recentlySuccessful = ref<boolean>(false)

  const submit = async (values: Record<string, any>) => {
    const onSuccess = options.onSuccess

    options.data = values
    options.preserveScroll = true
    options.onStart = () => (loading.value = true)
    options.onFinish = () => (loading.value = false)
    options.onSuccess = (...params) => {
      if (onSuccess) {
        onSuccess(...params)
      }

      recentlySuccessful.value = true
      setTimeout(() => {
        recentlySuccessful.value = false
      }, 3000)
    }
    router.visit(href, options)
  }

  return { submit, loading, recentlySuccessful }
}
