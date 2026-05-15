export const formatCurrency = (value) => {

    return Number(value || 0).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    })
}

export const formatDate = (date) => {

    if (!date) return '-'

    const formatted = date.split('T')[0]

    const [year, month, day] = formatted.split('-')

    return `${day}/${month}/${year}`
}