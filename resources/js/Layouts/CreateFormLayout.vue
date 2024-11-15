<script setup lang="ts" generic="TValues">
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card'

import { TypedSchema, useForm } from 'vee-validate'
import { useSubmit } from '@/Composables/submit'

const props = withDefaults(
  defineProps<{
    formSchema: TypedSchema
    initialValues?: TValues
    route: string
    disabled?: boolean
    btnTitle?: string
  }>(),
  {
    btnTitle: 'إنشاء',
  },
)

const emit = defineEmits(['success'])

const form = useForm({
  validationSchema: props.formSchema,
  initialValues: props.initialValues,
})

const { submit, loading, recentlySuccessful } = useSubmit(props.route, {
  method: 'post',
  preserveState: true,
  onSuccess: () => {
    emit('success')
    form.resetForm()
  },
  onError: (errors) => form.setErrors(errors),
})
const onSubmit = form.handleSubmit(submit)
</script>

<template>
  <form @submit="onSubmit">
    <Card>
      <CardHeader v-if="$slots.title">
        <slot name="title" />
      </CardHeader>
      <CardContent>
        <div
          class="grid grid-cols-1 gap-4 md:grid-cols-2"
          :class="{
            'pt-6': !$slots.title,
          }"
        >
          <slot :form="form" />
        </div>
      </CardContent>
      <CardFooter class="border-t px-6 py-4">
        <div class="flex items-center gap-4">
          <Button
            type="submit"
            :loading="loading"
            :disabled="disabled || loading || !form.meta.value.valid"
          >
            {{ btnTitle }}
          </Button>

          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-if="recentlySuccessful" class="text-sm text-gray-600">
              {{ `تم ال${btnTitle}.` }}
            </p>
          </Transition>
        </div>
      </CardFooter>
    </Card>
  </form>
</template>
