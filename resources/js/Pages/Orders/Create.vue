<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Card, User } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import OrderForm from './Partials/OrderForm.vue'

const props = defineProps<{
  sellers: Array<User>
  cards: Array<Card>
  statuses: Array<string>
}>()

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
    status: 'معلق',
    cards: [
      {
        card_id: String(props.cards[0]?.id),
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
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلبية جديدة
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit">
      <OrderForm
        :sellers="sellers"
        :cards="cards"
        :selected="values.seller_id"
        @select="(selected: number) => setFieldValue('seller_id', selected)"
      />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
