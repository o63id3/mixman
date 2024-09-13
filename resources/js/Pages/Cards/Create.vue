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
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/Components/ui/number-field'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'
import Textarea from '@/Components/ui/textarea/Textarea.vue'

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    price_for_consumer: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    price_for_seller: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, setFieldValue } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('cards.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء الكرت' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        كرت جديد
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem class="md:col-span-2" v-auto-animate>
                    <FormLabel>اسم الكرت</FormLabel>
                    <FormControl>
                      <Input
                        type="text"
                        placeholder="كرت فئة 1 شيكل"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ value }" name="price_for_consumer">
                  <FormItem>
                    <FormLabel>السعر للمستهلك</FormLabel>
                    <NumberField
                      class="ltr gap-2"
                      :min="0"
                      :step="0.01"
                      :model-value="value"
                      @update:model-value="
                        (newValue) =>
                          setFieldValue(
                            'price_for_consumer',
                            newValue ? newValue : undefined,
                          )
                      "
                    >
                      <NumberFieldContent>
                        <NumberFieldDecrement />
                        <FormControl>
                          <NumberFieldInput />
                        </FormControl>
                        <NumberFieldIncrement />
                      </NumberFieldContent>
                    </NumberField>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ value }" name="price_for_seller">
                  <FormItem>
                    <FormLabel>السعر للبائع</FormLabel>
                    <NumberField
                      class="ltr gap-2"
                      :min="0"
                      :step="0.01"
                      :model-value="value"
                      @update:model-value="
                        (newValue) =>
                          setFieldValue(
                            'price_for_seller',
                            newValue ? newValue : undefined,
                          )
                      "
                    >
                      <NumberFieldContent>
                        <NumberFieldDecrement />
                        <FormControl>
                          <NumberFieldInput />
                        </FormControl>
                        <NumberFieldIncrement />
                      </NumberFieldContent>
                    </NumberField>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2" v-auto-animate>
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <Button type="submit"> إنشاء </Button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
