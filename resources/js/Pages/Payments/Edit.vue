<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button, buttonVariants } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { toast } from '@/Components/ui/toast'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/Components/ui/command'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/Components/ui/number-field'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { Payment, User } from '@/types'
import { cn } from '@/lib/utils'
import { CheckIcon } from 'lucide-vue-next'
import { CaretSortIcon } from '@radix-icons/vue'
import Combobox from '@/Components/combobox/Combobox.vue'

const props = defineProps<{
  payment: Payment
  sellers: Array<User>
}>()

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    seller_id: props.payment.seller.id,
    amount: props.payment.amount,
    notes: props.payment.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('payments.update', props.payment.id), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم تعديل الدفعة المالية' })
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ payment.id }}
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
              <div class="flex gap-2">
                <Button type="submit"> تعديل </Button>
                <Link
                  :href="route('payments.destroy', payment.id)"
                  method="delete"
                  as="button"
                  type="button"
                  :class="cn(buttonVariants({ variant: 'destructive' }))"
                  @success="toast({ title: 'تم حذف الدفعة' })"
                >
                  حذف
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
