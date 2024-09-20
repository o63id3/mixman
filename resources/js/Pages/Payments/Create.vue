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
import { toast } from '@/Components/ui/toast'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/Components/ui/number-field'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import { User } from '@/types'
import Combobox from '@/Components/combobox/Combobox.vue'

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('payments.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إدخال الدفعة المالية' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})

defineProps<{
  sellers: Array<User>
}>()
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField name="seller_id">
                  <FormItem class="flex flex-col gap-2">
                    <FormLabel>نقطة البيع</FormLabel>
                    <Combobox
                      :options="
                        sellers.map((seller) => ({
                          value: seller.id,
                          label: seller.name,
                        }))
                      "
                      choose-title="اختر بائع..."
                      search-placeholder="ابحث عن نقطة بيع"
                      :selected="values.seller_id"
                      @select="
                        (selected: any) =>
                          setFieldValue('seller_id', selected.value)
                      "
                    />
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ value }" name="amount">
                  <FormItem>
                    <FormLabel>المبلغ</FormLabel>
                    <NumberField
                      class="ltr gap-2"
                      :min="0"
                      :step="0.01"
                      :model-value="value"
                      @update:model-value="
                        (newValue) =>
                          setFieldValue(
                            'amount',
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
              <Button type="submit"> إنشاء </Button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
