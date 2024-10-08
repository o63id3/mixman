import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { toast } from '../ui/toast'

export function useSubmit(
  href: string,
  resetForm?: Function,
  setErrors?: Function,
  msg?: string,
  method: 'get' | 'post' | 'put' | 'patch' | 'delete' = 'post',
) {
  const loading = ref<boolean>(false)

  const submit = async (values: Record<string, any>) => {
    router.visit(href, {
      method: method,
      data: values,
      preserveScroll: true,
      preserveState: true,
      onStart: () => (loading.value = true),
      onFinish: () => (loading.value = false),
      onSuccess: () => {
        msg ? toast({ title: msg }) : undefined
        if (resetForm) resetForm()
      },
      onError: (errors) => (setErrors ? setErrors(errors) : undefined),
    })
  }

  return { loading, submit }
}
