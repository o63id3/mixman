<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { useForm } from 'vee-validate'
import { formSchema } from './definitions'
import { useSubmit } from '@/Composables/submit'
import OrderForm from './Partials/OrderForm.vue'

import { Card, User } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import { toast } from '@/Components/ui/toast'

const props = defineProps<{
  users: Array<User>
  cards: Array<Card>
  statuses: Array<string>
}>()

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

const { submit, loading } = useSubmit(route('orders.store'), {
  method: 'post',
  onSuccess: () => {
    toast({ title: 'تم إنشاء الطلب' })
    resetForm()
  },
  onError: (errors) => setErrors(errors),
})
const onSubmit = handleSubmit(submit)
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلبية جديدة
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit" :loading="loading">
      <OrderForm
        :users="users"
        :cards="cards"
        :selected="values.user_id"
        @select="(selected: number) => setFieldValue('user_id', selected)"
      />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
