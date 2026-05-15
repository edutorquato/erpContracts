export const isValidEmail = (email) => {

    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
}

export const isValidCpfCnpj = (value) => {

    if (!value) return false

    value = value.replace(/\D/g, '')

    // CPF
    if (value.length === 11) {

        if (/^(\d)\1+$/.test(value)) return false

        let sum = 0
        let remainder

        for (let i = 1; i <= 9; i++) {
            sum += parseInt(value.substring(i - 1, i)) * (11 - i)
        }

        remainder = (sum * 10) % 11

        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0
        }

        if (remainder !== parseInt(value.substring(9, 10))) {
            return false
        }

        sum = 0

        for (let i = 1; i <= 10; i++) {
            sum += parseInt(value.substring(i - 1, i)) * (12 - i)
        }

        remainder = (sum * 10) % 11

        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0
        }

        return remainder === parseInt(value.substring(10, 11))
    }

    // CNPJ
    if (value.length === 14) {

        if (/^(\d)\1+$/.test(value)) return false

        let length = value.length - 2
        let numbers = value.substring(0, length)
        const digits = value.substring(length)

        let sum = 0
        let pos = length - 7

        for (let i = length; i >= 1; i--) {

            sum += numbers.charAt(length - i) * pos--

            if (pos < 2) pos = 9
        }

        let result = sum % 11 < 2 ? 0 : 11 - sum % 11

        if (result != digits.charAt(0)) return false

        length = length + 1
        numbers = value.substring(0, length)

        sum = 0
        pos = length - 7

        for (let i = length; i >= 1; i--) {

            sum += numbers.charAt(length - i) * pos--

            if (pos < 2) pos = 9
        }

        result = sum % 11 < 2 ? 0 : 11 - sum % 11

        return result == digits.charAt(1)
    }

    return false
}