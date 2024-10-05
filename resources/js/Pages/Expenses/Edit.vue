<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Expense, Network } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import ExpenseForm from './Partials/ExpenseForm.vue'
import { useSubmit } from '@/Components/Composables/submit'

const props = defineProps<{
  expense: Expense
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

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    description: props.expense.description,
    network_id: String(props.expense.network.id),
    amount: props.expense.amount,
  },
})

const { submit, loading } = useSubmit(
  route('expenses.update', props.expense.id),
  undefined,
  setErrors,
  'تم تعديل المصروف',
  'patch',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Expenses" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية رقم {{ expense.id }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" :loading="loading" can-update>
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('expenses.destroy', expense.id)"
          @success="toast({ title: 'تم حذف المصروف' })"
        >
          حذف
        </DeleteLink>
      </template>

      <ExpenseForm :networks="networks" />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
