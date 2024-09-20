<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { Card } from '@/types'
import Switch from '@/Components/ui/switch/Switch.vue'
import Label from '@/Components/ui/label/Label.vue'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/Components/ui/number-field'

const props = defineProps<{
  card: Card
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    active: z.boolean(),
    price_for_consumer: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    price_for_seller: z.number({ message: 'هذا الحقل مطلوب' }).min(0),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.card.name,
    price_for_consumer: props.card.price_for_consumer,
    price_for_seller: props.card.price_for_seller,
    active: props.card.active,
    notes: props.card.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  console.log(values)

  router.patch(route('cards.update', props.card.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل الكرت' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Cards" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ card.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField v-slot="{ componentField }" name="name">
                  <FormItem class="col-span-2">
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
                  <FormItem class="md:col-span-2">
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
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
