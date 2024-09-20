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
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Card, User } from '@/types'
import { PlusCircleIcon } from 'lucide-vue-next'
import Combobox from '@/Components/combobox/Combobox.vue'
import CardItemForm from './Partials/CardItemForm.vue'

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    cards: z.array(
      z.object({
        card_id: z.string({ message: 'حقل الفئة مطلوب' }),
        number_of_packages: z.number({ message: 'حقل الفئة مطلوب' }).min(1),
        number_of_cards_per_package: z.number(),
      }),
    ),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    status: 'طلب جديد',
    cards: [
      {
        card_id: '1',
        number_of_packages: 1,
        number_of_cards_per_package: 120,
      },
    ],
  },
})

const onSubmit = handleSubmit((values) => {
  router.post(route('orders.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء الطلب' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})

defineProps<{
  sellers: Array<User>
  cards: Array<Card>
  statuses: Array<string>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلبية جديدة
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField name="seller_id">
                  <FormItem>
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
                <FormField v-slot="{ componentField }" name="status">
                  <FormItem>
                    <FormLabel>الحالة</FormLabel>
                    <FormControl>
                      <Select v-bind="componentField">
                        <SelectTrigger>
                          <SelectValue placeholder="اختر حالة الطلب" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectGroup>
                            <SelectItem
                              v-for="status in statuses"
                              :key="status"
                              :value="status"
                            >
                              {{ status }}
                            </SelectItem>
                          </SelectGroup>
                        </SelectContent>
                      </Select>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="cards">
                  <FormItem class="col-span-full">
                    <FormLabel>الكروت</FormLabel>
                    <FormControl class="col-span-full">
                      <CardItemForm
                        v-for="(card, index) in componentField.modelValue"
                        :key="index"
                        class="mb-2 flex gap-1 border-b pb-3"
                        :cards="cards"
                        :can-delete="componentField.modelValue.length > 1"
                        @delete="componentField.modelValue.splice(index, 1)"
                        v-model:card="card.card_id"
                        v-model:packages="card.number_of_packages"
                        v-model:cardsPerPackage="
                          card.number_of_cards_per_package
                        "
                      />
                      <div>
                        <Button
                          type="button"
                          variant="outline"
                          @click="
                            componentField.modelValue.push({
                              card_id: '1',
                              number_of_packages: 1,
                              number_of_cards_per_package: 120,
                            })
                          "
                        >
                          <PlusCircleIcon class="w-5" />
                        </Button>
                      </div>
                    </FormControl>
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
