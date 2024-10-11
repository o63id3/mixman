<script setup lang="ts" generic="TValues">
import { Button } from '@/Components/ui/button'

import { useSubmit } from '@/Composables/submit'
import { TypedSchema, useForm } from 'vee-validate'

const props = defineProps<{
  formSchema: TypedSchema
  initialValues?: TValues
  route: string
  canUpdate?: boolean
}>()

const emit = defineEmits(['success'])

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: props.formSchema,
  initialValues: props.initialValues,
})

const { submit, loading } = useSubmit(props.route, {
  method: 'patch',
  onSuccess: () => emit('success'),
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
        <div class="flex gap-2">
          <Button
            v-if="canUpdate"
            type="submit"
            :loading="loading"
            :disabled="loading"
          >
            حفظ
          </Button>
          <slot name="buttons" />
        </div>
      </form>
    </div>
  </div>
</template>
