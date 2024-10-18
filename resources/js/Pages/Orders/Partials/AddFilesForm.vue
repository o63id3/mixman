<script setup lang="ts">
import CreateFormLayout from '@/Layouts/CreateFormLayout.vue'

import {
  FormControl,
  FormField,
  FormItem,
  FormMessage,
} from '@/Components/ui/form'

import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import MultiFileUploader from '@/Components/file-upload/MultiFileUploader.vue'
import { Order } from '@/types'
import { toast } from '@/Components/ui/toast'

defineProps<{
  order: Order
}>()

const emit = defineEmits(['success'])

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

const initialValues = {
  files: [],
}
</script>

<template>
  <CreateFormLayout
    :form-schema="formSchema"
    :initial-values="initialValues"
    :route="route('order-files.store', order.id)"
    @success="
      () => {
        toast({ title: 'تم إضافة الملفات' })
        emit('success')
      }
    "
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
