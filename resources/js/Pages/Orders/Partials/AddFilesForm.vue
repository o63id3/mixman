<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from '@/Components/ui/form'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import MultiFileUploader from '@/Components/file-upload/MultiFileUploader.vue'
import { useSubmit } from '@/Components/Composables/submit'
import { Order } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'

const props = defineProps<{
  order: Order
}>()

const formSchema = toTypedSchema(
  z.object({
    files: z
      .array(
        z.object({
          server_path: z.string(),
          original_file_name: z.string(),
          extension: z.string(),
          size: z.number(),
        }),
      )
      .optional(),
  }),
)

const { handleSubmit, setErrors, resetField, meta } = useForm({
  validationSchema: formSchema,
  initialValues: {
    files: [],
  },
})

const { submit, loading } = useSubmit(
  route('order-files.store', props.order.id),
  resetField,
  setErrors,
  'تم إضافة الملفات',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <CreateFormLayout
    class="mt-4"
    @submit="onSubmit"
    :loading="loading"
    :disabled="!meta.dirty"
    btn-title="إضافة"
  >
    <FormField v-slot="{ componentField }" name="files">
      <FormItem class="col-span-full">
        <FormControl class="col-span-full">
          <MultiFileUploader
            @uploaded="(file: string) => componentField.modelValue.push(file)"
            @delete="(key: number) => componentField.modelValue.splice(key, 1)"
          />
        </FormControl>
        <FormMessage />
      </FormItem>
    </FormField>
  </CreateFormLayout>
</template>
