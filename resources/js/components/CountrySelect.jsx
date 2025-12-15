import React, { useEffect, useState } from 'react';

const CountrySelect = ({
    className = '',
    required = false,
    value,
    onChange,
    ...restProps
}) => {
    const [countries, setCountries] = useState([]);
    const [countryLoading, setCountryLoading] = useState(false);
    const [selectedCountry, setSelectedCountry] = useState('');

    useEffect(() => {
        let mounted = true;
        const fetchCountries = async () => {
            setCountryLoading(true);
            try {
                const response = await fetch("https://countriesnow.space/api/v0.1/countries");
                const data = await response.json();
                if (mounted) {
                    if (data && Array.isArray(data.data)) {
                        setCountries(data.data);
                    } else {
                        setCountries([]);
                    }
                    setCountryLoading(false);
                }
            } catch {
                if (mounted) {
                    setCountries([]);
                    setCountryLoading(false);
                }
            }
        };
        fetchCountries();
        return () => { mounted = false; };
    }, []);

    const handleCountryChange = e => {
        setSelectedCountry(e.target.value);
        if (onChange) onChange(e);
    };

    const selectValue = typeof value !== "undefined" ? value : selectedCountry;

    return (
        <select
            className={className}
            value={selectValue}
            required={required}
            onChange={handleCountryChange}
            {...restProps}
        >
            <option value="">Select a country</option>
            {countryLoading && (
                <option value="" disabled>
                    Loading countries...
                </option>
            )}
            {!countryLoading && countries.length > 0 && countries.map((country, idx) => (
                <option key={country.country + idx} value={country.country}>
                    {country.country}
                </option>
            ))}
        </select>
    );
};

export default CountrySelect;
