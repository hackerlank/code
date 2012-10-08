void insertsort(int *digits)
{
    int i,j,current_digit;
    for (j = 1; '\0' != digits[j]; j++) {
        i = j -1;
        current_digit = digits[j];
        while (i >= 0 && digits[i] > current_digit) {
            digits[i+1] = digits[i];
            i = i -1;
        } 
        digits[i+1] = current_digit;
    }
}
