<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { vAutoAnimate } from '@formkit/auto-animate/vue'

import { Button } from '@/Components/ui/button'
import {
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Region } from '@/types'

const props = defineProps<{
  region: Region
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.region.name,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('regions.update', props.region.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل المنطقة' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Regions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ region.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem v-auto-animate>
                    <FormLabel>اسم المنطقة</FormLabel>
                    <FormControl>
                      <Input
                        type="text"
                        placeholder="كمال عدوان"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <Button type="submit"> تعديل </Button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
