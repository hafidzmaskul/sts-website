import React from 'react';
import ScaffoldBase from './_ScaffoldBase';

export default function ServiceDetail({ slug }) {
  return (
    <ScaffoldBase title={`Service: ${slug}`}>
      <p>Service detail for {slug}.</p>
    </ScaffoldBase>
  );
}

