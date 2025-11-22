import React, { useRef, useState, Children, forwardRef, useImperativeHandle } from 'react';
import './CircularCarousel.css';

export function lerp(start, stop, amt) {
  return (1 - amt) * start + amt * stop;
}

const ANGLE_PER_ITEM = 100;
const CENTER_OFFSET = 0;
const FULL_ROTATION = 360;
const ACTIVE_ANGLE_THRESHOLD = 8;

function normalizeAngle(angle) {
  const normalized = angle % FULL_ROTATION;
  return normalized < 0 ? normalized + FULL_ROTATION : normalized;
}

function distanceToTop(angle) {
  const normalized = normalizeAngle(angle - CENTER_OFFSET);
  return Math.min(normalized, FULL_ROTATION - normalized);
}

function findUprightIndex(currentDeg, itemCount) {
  if (itemCount === 0) {
    return 0;
  }

  let closestIndex = 0;
  let closestDistance = Number.POSITIVE_INFINITY;

  for (let i = 0; i < itemCount; i += 1) {
    const angle = (i * ANGLE_PER_ITEM) + currentDeg;
    const distance = distanceToTop(angle);

    if (distance < closestDistance) {
      closestDistance = distance;
      closestIndex = i;
    }
  }

  return closestIndex;
}

function CircularCarouselComp(
  { onSelect, onSwapRight, onPointerDown, children },
  ref
) {
  const childArray = Children.toArray(children);
  const len = childArray.length;
  const indexRef = useRef(0);
  const prevRef = useRef(0);
  const nextRef = useRef(0);
  const rendering = useRef(false);
  const [deg, setDeg] = useState(CENTER_OFFSET);
  const [wrapper, setWrapper] = useState(null);

  const handleSetWrapper = (ref) => {
    setWrapper(ref);
  };

  function move() {
    if (len === 0) {
      return;
    }

    const next = nextRef.current;
    const prev = prevRef.current;
    const currentDeg = lerp(prev, next, 0.15);
    if (currentDeg !== prev) {
      setDeg(currentDeg);
      prevRef.current = currentDeg;
      requestAnimationFrame(move);
    } else {
      rendering.current = false;
    }

    const uprightIndex = findUprightIndex(currentDeg, len);
    if (uprightIndex !== indexRef.current) {
      indexRef.current = uprightIndex;
      onSelect && onSelect(uprightIndex);
    }
  }

  const onMouseDown = (e) => {
    const isTouch = e.type === "touchstart";
    let _deg = deg;

    onPointerDown && onPointerDown();

    const tryMove = (next) => {
      _deg = nextRef.current += next;
      if (!rendering.current) {
        rendering.current = true;
        requestAnimationFrame(move);
      }
    };

    const onMouseMove = ({ movementX }) => {
      tryMove(movementX / 30);
    };

    let prevTouchPageX;
    const onTouchMove = ({ touches }) => {
      const pageX = touches[0].pageX;
      if (prevTouchPageX) {
        const movementX = pageX - prevTouchPageX;
        tryMove(movementX / 10);
      }
      prevTouchPageX = pageX;
    };

  const onMouseUp = () => {
    document.removeEventListener("touchmove", onTouchMove);
    document.removeEventListener("mousemove", onMouseMove);
    document.removeEventListener("mouseup", onMouseUp);
    document.removeEventListener("touchend", onMouseUp);

    if (len === 0) {
      return;
    }

    nextRef.current = _deg;
    if (!rendering.current) {
      rendering.current = true;
      requestAnimationFrame(move);
    }
  };
    if (isTouch) {
      document.addEventListener("touchmove", onTouchMove);
      document.addEventListener("touchend", onMouseUp);
    } else {
      document.addEventListener("mousemove", onMouseMove);
      document.addEventListener("mouseup", onMouseUp);
    }
  };

  React.useEffect(() => {
    // No wheel event listener - scroll is disabled
    return () => {
      // Cleanup if needed
    };
  }, []);


  useImperativeHandle(
    ref,
    () => ({
      scrollTo(i) {
        if (len === 0) {
          return;
        }
        
        const totalArc = ANGLE_PER_ITEM * len;
        const currentNormalized = ((-(deg - CENTER_OFFSET) % totalArc) + totalArc) % totalArc;
        const currentIndex = Math.round(currentNormalized / ANGLE_PER_ITEM) % len;
        
        let targetIndex = i % len;
        if (targetIndex < 0) {
          targetIndex += len;
        }
        
        let indexDiff = targetIndex - currentIndex;
        
        if (indexDiff === 0) {
          return;
        }
        
        if (indexDiff < 0) {
          indexDiff += len;
        }
        
        if (indexDiff > 1) {
          indexDiff = 1;
        }
        
        const degDiff = -indexDiff * ANGLE_PER_ITEM;
        nextRef.current = deg + degDiff;
        
        if (!rendering.current) {
          rendering.current = true;
          requestAnimationFrame(move);
        }
      },
    }),
    [len, deg],
  );

  const renderItems = () => {
    if (len === 0) {
      return null;
    }

    const items = [];
    const totalCopies = 3;

    for (let copy = -1; copy <= totalCopies; copy++) {
      childArray.forEach((child, i) => {
        const absoluteIndex = copy * len + i;
        const angle = absoluteIndex * ANGLE_PER_ITEM;
        const normalizedAngle = normalizeAngle(angle + deg);
        const isActive = distanceToTop(normalizedAngle) <= ACTIVE_ANGLE_THRESHOLD;

        items.push(
          <div
            key={`${copy}-${i}`}
            className="circular-carousel-item"
            style={{
              transform: `translateX(-50%) rotate(${angle}deg)`,
            }}
          >
            {React.cloneElement(child, {
              className: `${child.props.className ?? ''}${isActive ? ' active' : ''}`.trim(),
            })}
          </div>
        );
      });
    }

    return items;
  };

  return (
    <div className="circular-carousel-root" ref={handleSetWrapper}>
      <div
        className="circular-carousel-handle"
        onMouseDown={onMouseDown}
        onTouchStart={onMouseDown}
      >
        <div className="circular-carousel-center">
          <div
            className="circular-carousel-items"
            style={{ transform: `rotate(${deg}deg)` }}
          >
            {renderItems()}
          </div>
        </div>
      </div>
    </div>
  );
}

const CircularCarousel = forwardRef(CircularCarouselComp);

export default CircularCarousel;
