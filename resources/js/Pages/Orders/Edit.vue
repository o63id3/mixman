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
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Card, Order, OrderItem, User } from '@/types'
import { cn } from '@/lib/utils'
import Combobox from '@/Components/combobox/Combobox.vue'
import DataTable from '@/Components/data-table/DataTable.vue'
import { columns } from './itemsColumns'
import { ref } from 'vue'
import { summaryFields } from './itemsColumns'
import AddItemsForm from './Partials/AddItemsForm.vue'
import { orderStatues } from '@/types/enums'
import Input from '@/Components/ui/input/Input.vue'

const props = defineProps<{
  order: Order
  items: Array<OrderItem>
  sellers: Array<User>
  statuses: Array<string>
  cards: Array<Card>
  can: {
    addItem: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    seller_id: props.order.seller.id,
    status: props.order.status,
    notes: props.order.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('orders.update', props.order.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل الطلب' }),
    onError: (errors) => setErrors(errors),
  })
})

const addingForm = ref(false)
</script>

<template>
  <Head title="orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.id }}
      </h2>
    </template>

    <div class="pt-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField name="seller_id">
                  <FormItem class="flex flex-col gap-2">
                    <FormLabel>نقطة البيع</FormLabel>
                    <Combobox
                      v-if="$page.props.auth.user.admin"
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
                    <Input v-else disabled v-model="order.seller.name" />
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
                              v-for="status in orderStatues"
                              :key="status.value"
                              :value="status.value"
                              :disabled="!order.can.update"
                            >
                              <div class="flex items-center justify-end gap-1">
                                {{ status.label }}
                                <component :is="status.icon" class="w-4" />
                              </div>
                            </SelectItem>
                          </SelectGroup>
                        </SelectContent>
                      </Select>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2">
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea
                        :disabled="!order.can.update"
                        v-bind="componentField"
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <div class="flex gap-2">
                <Button v-if="order.can.update" type="submit"> تعديل </Button>
                <Link
                  v-if="order.can.delete"
                  :href="route('orders.destroy', order.id)"
                  method="delete"
                  as="button"
                  type="button"
                  :class="cn(buttonVariants({ variant: 'destructive' }))"
                  @success="toast({ title: 'تم حذف الطلب' })"
                >
                  حذف
                </Link>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="pb-8 pt-5">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between px-4">
          <p class="text-sm font-medium tracking-wide"># الكروت</p>
          <Button
            class="text-xs tracking-wide"
            size="xs"
            @click="addingForm = true"
            v-if="!addingForm && can.addItem"
          >
            إضافة رزم
          </Button>
        </div>
        <div v-if="!addingForm" class="mt-4 space-y-4">
          <div class="overflow-hidden bg-white shadow-sm lg:rounded-md">
            <DataTable
              :data="items"
              :columns="columns"
              :summaryFields="items.length ? summaryFields : undefined"
            />
          </div>
        </div>
        <div
          v-if="addingForm && can.addItem"
          class="mt-4 overflow-hidden bg-white shadow-sm sm:rounded-lg"
        >
          <div class="p-6 text-gray-900">
            <AddItemsForm
              :cards="cards"
              :order="order"
              @success="addingForm = false"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
