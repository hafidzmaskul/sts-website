import React from 'react';
import ScaffoldBase from './_ScaffoldBase';

export default function ProductDetail({ slug }) {
  return (
    <ScaffoldBase title={`Product: ${slug}`}>
      <p>Product detail for {slug}.</p>
    </ScaffoldBase>
  );
}

