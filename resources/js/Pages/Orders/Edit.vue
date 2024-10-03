<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Card, Order, OrderItem, User } from '@/types'
import OrderForm from './Partials/OrderForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import CardsSection from './Partials/CardsSection.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

const props = defineProps<{
  order: Order
  items: Array<OrderItem>
  users?: Array<User>
  statuses: Array<string>
  cards?: Array<Card>
  can: {
    addItem: boolean
    update: boolean
    delete: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    orderer_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    orderer_id: props.order.orderer.id,
    status: props.order.status,
    notes: props.order.notes ?? undefined,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('orders.update', props.order.id), values, {
    preserveScroll: true,
    preserveState: false,
    onSuccess: () => toast({ title: 'تم تعديل الطلب' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.id }}
        <span class="text-xs font-normal tracking-wide">
          ({{ order.manager.name }})
        </span>
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" :can-update="can.update">
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('orders.destroy', order.id)"
          @success="toast({ title: 'تم حذف الطلب' })"
        >
          حذف
        </DeleteLink>
      </template>

      <OrderForm
        hidden-cards
        :disabled="!can.update"
        :order="order"
        :users="users"
        :cards="cards"
        :selected="values.orderer_id"
        @select="(selected: number) => setFieldValue('orderer_id', selected)"
      />
    </UpdateFormLayout>

    <CardsSection
      class="mt-4"
      editing
      :can-update="can.update"
      :items="items"
      :order="order"
      :cards="cards"
      :can-add-item="can.addItem"
    />
  </AuthenticatedLayout>
</template>
