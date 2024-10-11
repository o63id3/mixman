<script setup lang="ts" generic="TValues">
import { Button } from '@/Components/ui/button'

import { TypedSchema, useForm } from 'vee-validate'
import { useSubmit } from '@/Composables/submit'

const props = defineProps<{
  formSchema: TypedSchema
  initialValues?: TValues
  route: string
  disabled?: boolean
  btnTitle?: string
}>()

const emit = defineEmits(['success'])

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: props.formSchema,
  initialValues: props.initialValues,
})

const { submit, loading } = useSubmit(props.route, {
  method: 'post',
  onSuccess: () => {
    emit('success')
    resetForm()
  },
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
      <form class="space-y-6" @submit="onSubmit">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <slot :values="values" :setFieldValue="setFieldValue" />
        </div>
        <Button
          type="submit"
          :loading="loading"
          :disabled="disabled || loading"
        >
          {{ btnTitle ?? 'إنشاء' }}
        </Button>
      </form>
    </div>
  </div>
</template>
