import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import type { VisitOptions } from '@inertiajs/core'

export function useSubmit(href: string, options: VisitOptions) {
  const loading = ref<boolean>(false)

  const submit = async (values: Record<string, any>) => {
    options.data = values
    options.preserveScroll = true
    options.preserveState = true
    options.onStart = () => (loading.value = true)
    options.onFinish = () => (loading.value = false)
    router.visit(href, options)
  }

  return { loading, submit }
}
