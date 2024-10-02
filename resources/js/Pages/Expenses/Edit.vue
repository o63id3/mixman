<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Data, Expense, Network, User } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import ExpenseForm from './Partials/ExpenseForm.vue'

const props = defineProps<{
  expense: Data<Expense>
  networks: Array<Network>
  can: {
    delete: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    description: z.string({ message: 'هذا الحقل مطلوب' }),
    network_id: z.string({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    description: props.expense.data.description,
    network_id: String(props.expense.data.network.id),
    amount: props.expense.data.amount,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('expenses.update', props.expense.data.id), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم تعديل المصروف' })
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Expenses" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ expense.data.id }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" can-update>
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('expenses.destroy', expense.data.id)"
          @success="toast({ title: 'تم حذف المصروف' })"
        >
          حذف
        </DeleteLink>
      </template>

      <ExpenseForm :networks="networks" />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
